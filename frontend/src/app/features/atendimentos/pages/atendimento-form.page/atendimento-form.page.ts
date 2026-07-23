import { Component, computed, inject, signal } from '@angular/core';
import { PageHeader } from "../../../../shared/components/page-header/page-header";
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { NotificationService } from '../../../../core/services/notification.service';
import { EntitySelectOption } from '../../../../shared/types/entity-select-option.type';
import { MedicoService } from '../../../medicos/services/medico.service';
import { PacienteService } from '../../../pacientes/services/paciente.service';
import { STATUS_ATENDIMENTO_LABELS, STATUS_ATENDIMENTO_VALUES } from '../../constants/atendimento.constant';
import { StatusAtendimento } from '../../enums/status-atendimento.enum';
import { AtendimentoService } from '../../services/atendimento.service';
import { AtendimentoApiModel } from '../../models/atendimento.model';
import { ApiResponseBase } from '../../../../shared/types/api-response.type';
import { applyErrorsToForm, getControlError } from '../../../../shared/constants/form-errors.constant';
import { MatButton } from '@angular/material/button';
import { MatOption } from '@angular/material/core';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { EntitySelect } from '../../../../shared/components/entity-select/entity-select';
import { formatDateToString, formatStringDate, formatStringDateToDate } from '../../../../shared/constants/format-date.constant';
import { MatProgressBar } from "@angular/material/progress-bar";
import { finalize } from 'rxjs';
import { LoadingSpinner } from "../../../../shared/components/loading-spinner/loading-spinner";

@Component({
  selector: 'app-atendimento-form.page',
  imports: [
    PageHeader,
    EntitySelect,
    MatButton,
    MatDatepickerModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatOption,
    ReactiveFormsModule,
    MatProgressBar,
    LoadingSpinner
],
  templateUrl: './atendimento-form.page.html',
  styleUrl: './atendimento-form.page.scss',
})
export class AtendimentoFormPage {

  private activeRoute = inject(ActivatedRoute);
  private router = inject(Router);
  private atendimentoService = inject(AtendimentoService);
  private medicoService = inject(MedicoService);
  private pacienteService = inject(PacienteService);
  private notificationService = inject(NotificationService);
  private formBuilder = inject(FormBuilder);
  private atendimentoId = signal<number | null>(null);

  protected atendimentoForm = this.formBuilder.nonNullable.group({
    data_atendimento: [null as Date | null, Validators.required],
    valor_consulta: ['', Validators.required],
    status: [StatusAtendimento.Agendado, Validators.required],
    paciente_id: [null as number | null, Validators.required],
    medico_id: [null as number | null, Validators.required],
  });
  
  protected loading = signal(true);
  protected saveLoading = signal(false);
  protected isEdit = computed(() => this.atendimentoId()); 

  protected readonly getControlError = getControlError;
  protected readonly STATUS_ATENDIMENTO_LABELS = STATUS_ATENDIMENTO_LABELS;
  protected readonly statusOptions = signal<StatusAtendimento[]>(STATUS_ATENDIMENTO_VALUES);
  protected medicoOptions = signal<EntitySelectOption[]>([]);
  protected pacienteOptions = signal<EntitySelectOption[]>([]);

  constructor() {
    const id = this.activeRoute.snapshot.paramMap.get('id');

    if(id){
      this.atendimentoId.set(id ? Number(id) : null);

      this.atendimentoService.getById(this.atendimentoId()!)
      .pipe(
          finalize(() => this.loading.set(false))
      )
      .subscribe({
        next: (atendimento) => {
            this.atendimentoForm.patchValue({
              ...atendimento,
              data_atendimento: formatStringDateToDate(atendimento.data_atendimento,'YMD','-'),
          });
        },
      })
    } else { /* Não valida status se for criar */
      this.loading.set(false);
      this.atendimentoForm.get('status')?.clearValidators();
      this.atendimentoForm.get('status')?.updateValueAndValidity();
    }

    this.medicoService.getAllAsOptions().subscribe(options => {
      this.medicoOptions.set(options);
    });

    this.pacienteService.getAllAsOptions().subscribe(options => {
      this.pacienteOptions.set(options);
    });

  }

  onSaveEntity(): void {
    if (this.atendimentoForm.invalid){
      return;
    }

    const formValue = this.atendimentoForm.getRawValue();

    const atendimentoApiModel: AtendimentoApiModel = {
      ...formValue,
      data_atendimento: formatDateToString(formValue.data_atendimento!,'YMD','-'), /* pegar apenas 0000-00-00 */
      paciente_id: formValue.paciente_id as number,
      medico_id: formValue.medico_id as number,
    };

    if (this.isEdit()) {
      this.saveLoading.set(true);

      this.atendimentoService.update(this.atendimentoId()!, atendimentoApiModel)
        .pipe(
          finalize(() => this.saveLoading.set(false))
        )
        .subscribe({
          next: (response) => {
            this.router.navigate(['/atendimentos']);
            this.notificationService.showSuccess(response.message);
          },
          error: (response) => {
            const apiError = response.error as ApiResponseBase;
            if (apiError.errors) applyErrorsToForm(this.atendimentoForm,apiError.errors);
          }
        });

    } else {
      this.saveLoading.set(true);

      this.atendimentoService.create(atendimentoApiModel)
        .pipe(
            finalize(() => this.saveLoading.set(false))
        )
        .subscribe({
          next: (response) => {
            this.router.navigate(['/atendimentos']);
            this.notificationService.showSuccess(response.message);
          },
          error: (response) => {
            const apiError = response.error as ApiResponseBase;
            if (apiError.errors) applyErrorsToForm(this.atendimentoForm,apiError.errors);
          }
        });

    }
  }

  onReturnPage(): void {
    this.router.navigate(['/atendimentos']);
  }
}
