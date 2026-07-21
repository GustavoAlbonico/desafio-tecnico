import { Component, computed, inject, signal } from '@angular/core';
import { AtendimentoService } from '../../services/atendimento.service';
import { AtendimentoListModel } from '../../models/atendimento.model';
import { Pagination, PaginationParams } from '../../../../shared/types/pagination.type';
import { TableList } from '../../../../shared/components/table-list/table-list';
import { TableAction, TableActionEvent, TableColumnPipe, TableListSettings, TableSortSettings } from '../../../../shared/types/table-list.type';
import { Paginator } from "../../../../shared/components/paginator/paginator";
import { PageEvent } from '@angular/material/paginator';
import { Sort } from '@angular/material/sort';
import { takeUntilDestroyed, toObservable } from '@angular/core/rxjs-interop';
import { debounceTime, distinctUntilChanged, finalize, switchMap, tap } from 'rxjs';
import { ATENDIMENTO_SORT_FIELD_MAP, STATUS_ATENDIMENTO_LABELS, STATUS_ATENDIMENTO_VALUES } from '../../constants/atendimento.constant';
import { EntitySelect } from "../../../../shared/components/entity-select/entity-select";
import { EntitySelectOption } from '../../../../shared/types/entity-select-option.type';
import { MedicoService } from '../../../medicos/services/medico.service';
import { PacienteService } from '../../../pacientes/services/paciente.service';
import { MatButton } from '@angular/material/button';
import { PageHeader } from "../../../../shared/components/page-header/page-header";
import { EmptyPage } from "../../../../shared/components/empty-page/empty-page";
import { FilterParams } from '../../../../shared/types/filter-params.type';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import { FormBuilder, FormGroup, ReactiveFormsModule } from '@angular/forms';
import { formatDateToString, formatStringDate } from '../../../../shared/constants/format-date.constant';
import { StatusAtendimento } from '../../enums/status-atendimento.enum';
import { MatOption } from "@angular/material/core";
import { MatSelectModule } from '@angular/material/select';
import { dateRangeValidator } from '../../validators/date-range.validator';
import { ConfirmDialog } from "../../../../shared/components/confirm-dialog/confirm-dialog";
import { MatDialog } from '@angular/material/dialog';
import { NotificationService } from '../../../../core/services/notification.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-atendimento-list.page',
  imports: [
    TableList,
    Paginator,
    PageHeader,
    EntitySelect,
    MatButton,
    MatDatepickerModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatOption,
    ReactiveFormsModule,
    EmptyPage,
],
  templateUrl: './atendimento-list.page.html',
  styleUrl: './atendimento-list.page.scss',
})
export class AtendimentoListPage {
  private router = inject(Router);
  private atendimentoService = inject(AtendimentoService);
  private medicoService = inject(MedicoService);
  private pacienteService = inject(PacienteService);
  private notificationService = inject(NotificationService);
  private formBuilder = inject(FormBuilder);
  private readonly dialog = inject(MatDialog);

  protected loading = signal(true);
  protected isFirstLoading = signal(true);
  protected atendimentos = signal<AtendimentoListModel[]>([]);

  /* configurações de tabela */
  protected tableListSettings: TableListSettings<AtendimentoListModel>[] = [
    { 
      reference: 'dataAtendimento', columnName: 'Data',
      value: (item) => formatStringDate(item.dataAtendimento,'YMD','DMY','-','/'), align: 'center'
    },
    { reference: 'valorConsulta', columnName: 'Valor', value: (item) => item.valorConsulta, align: 'center', pipe: {type:'currency'} as TableColumnPipe },
    { 
      reference: 'status', columnName: 'Status', align: 'center',
      value: (item) => STATUS_ATENDIMENTO_LABELS[item.status], 
      class: (item) => {
        switch (item.status) {
          case StatusAtendimento.Cancelado: return 'cancelado';
          case StatusAtendimento.Concluido: return 'concluido';
          case StatusAtendimento.Agendado: return 'agendado';
          default: return '';
        }
      }
    },
    { reference: 'paciente', columnName: 'Paciente', value: (item) => item.pacienteNome, },
    { reference: 'medico', columnName: 'Médico', value: (item) => item.medicoNome, },
  ];

  protected tableActions: TableAction[] = [
    { name: 'editar', icon: 'edit', label: 'Editar' },
    { name: 'excluir', icon: 'delete', label: 'Excluir' },
  ];

  protected tableSortSettings: TableSortSettings = {
    sort: 'dataAtendimento',
    direction: 'desc',
  };

  /* configurações de paginação */
  protected paginationPageOptions: number[] = [5, 12, 26, 50];

  protected paginationParams = signal<PaginationParams>({
    page: 1,
    limit: 12,
    direction: this.tableSortSettings.direction,
    sort: ATENDIMENTO_SORT_FIELD_MAP[this.tableSortSettings.sort],
  });

  pagination = signal<Pagination | null>(null);

  /* informações sobre filtros */
  protected filterForm: FormGroup = this.formBuilder.group(
    {
      medicoId: [null as number | null],
      pacienteId: [null as number | null],
      status: [null as number | null],
      dataInicial: [null as Date | null],
      dataFinal: [null as Date | null],
    }, 
    { validators : dateRangeValidator('dataInicial','dataFinal')}
  );

  protected appliedFilters = signal<FilterParams>({});
  protected hasActiveFilters = computed(() => !this.isEmptyObject(this.appliedFilters()));

  protected STATUS_ATENDIMENTO_LABELS = STATUS_ATENDIMENTO_LABELS;
  protected medicoOptions = signal<EntitySelectOption[]>([]);
  protected pacienteOptions = signal<EntitySelectOption[]>([]);
  protected statusOptions = signal<StatusAtendimento[]>(STATUS_ATENDIMENTO_VALUES);

  /* computed para gatilho do toObservable de filtros e parametros de paginação */
  protected queryParams = computed(() => ({
    ...this.paginationParams(),
    ...this.appliedFilters(),
  }));

  constructor() {

    toObservable(this.queryParams)
      .pipe(

        /*
          -> liga o loading 
          -> Toda vez que paginationParams mudar ou filters
          -> espera 300ms de silêncio
          -> se realmente for diferente do valor anterior 
          -> cancela qualquer requisição anterior ainda pendente e faz a nova 
          -> e faz tudo isso só enquanto o componente estiver vivo na tela
        */
        tap(() => this.loading.set(true)),
        debounceTime(300),
        distinctUntilChanged((prev, curr) => JSON.stringify(prev) === JSON.stringify(curr)),
        switchMap((params) => this.atendimentoService.getAll(params).pipe(finalize(() => this.loading.set(false)))),
        takeUntilDestroyed()
      )
      .subscribe({
        next: (paginatedModel) => {
          this.atendimentos.set(paginatedModel.items);
          this.pagination.set(paginatedModel.pagination);

          if (!this.isFirstLoading()) return;
          this.isFirstLoading.set(false);
        },
        error: () => {

          if (!this.isFirstLoading()) return;
          this.isFirstLoading.set(false);
        }
      });

    this.medicoService.getAllAsOptions().subscribe(options => {
      this.medicoOptions.set(options);
    });

    this.pacienteService.getAllAsOptions().subscribe(options => {
      this.pacienteOptions.set(options);
    });

  }

  onTableAction(event: TableActionEvent<AtendimentoListModel>): void {
    switch (event.name) {
      case 'editar':
        this.router.navigate(['/atendimentos/editar/', event.item.id]);
        break;
      case 'excluir':
        this.confirmDelete(event.item);
        break;
    }
  }

  onPageEvent(event: PageEvent): void {
    this.paginationParams.update((params) => ({
      ...params,
      page: event.pageIndex + 1,
      limit: event.pageSize,
    }));
  }

  onTableSortChange(sort: Sort): void {

    this.paginationParams.update((params) => ({
      ...params,
      sort: ATENDIMENTO_SORT_FIELD_MAP[sort.active],
      direction: sort.direction,
    }));
  }

  onClearFilters(): void {
    if (!this.hasActiveFilters()) return;

    this.paginationParams.update((params) => ({ ...params, page: 1 }));
    this.filterForm.reset();
    this.appliedFilters.set({});
  }

  onApplyFilters(): void {
    const formValue = this.filterForm.value;
    const filters: FilterParams = {};

    if (formValue.medicoId !== null) {
      filters['medico_id'] = formValue.medicoId;
    }
    if (formValue.pacienteId !== null) {
      filters['paciente_id'] = formValue.pacienteId;
    }
    if (formValue.status !== null) {
      filters['status'] = formValue.status;
    }
    if (formValue.dataInicial) {
      filters['data_inicial'] = formatDateToString(formValue.dataInicial,'YMD','-');
    }
    if (formValue.dataFinal) {
      filters['data_final'] = formatDateToString(formValue.dataFinal,'YMD','-');
    }

    if (this.isEmptyObject(filters)) return;

    this.paginationParams.update((params) => ({ ...params, page: 1 }));
    this.appliedFilters.set(filters);
  }

  onCreate(): void{
    this.router.navigate(['/atendimentos/novo']);
  }

  confirmDelete(item: AtendimentoListModel): void {
    const dialogRef = this.dialog.open(ConfirmDialog, {
      data: {
        title: 'Excluir atendimento',
        description: `Tem certeza que deseja excluir este atendimento ${formatStringDate(item.dataAtendimento,'YMD','DMY','-','/')} da paciente ${item.pacienteNome} e médico responsável ${item.medicoNome}? Essa ação não pode ser desfeita.`,
        leftButtonLabel: 'Cancelar',
        rightButtonLabel: 'Excluir',
      },
    });

    dialogRef.afterClosed().subscribe(confirmado => {
      if (confirmado) {
        this.excluirAtendimento(item);
      }
    });
  }

  /* verifica se o objeto está vazio */
  private isEmptyObject(object: Object): boolean {
    return Object.keys(object).length === 0;
  }

  private excluirAtendimento(item: AtendimentoListModel): void {
    this.loading.set(true);

    /* precisei reutilizar esta parte sem o debounce para evitar do loading travar (problema de UI) */
    this.atendimentoService.delete(item.id)
    .pipe(
        tap(response => {
          if (response) {
            this.notificationService.showSuccess(response.message);
          }
        }),
        switchMap(() => this.atendimentoService.getAll(this.queryParams())),
        finalize(() => this.loading.set(false))
      )
      .subscribe(paginatedModel => {
        this.atendimentos.set(paginatedModel.items);
        this.pagination.set(paginatedModel.pagination);
      });
  }

}

