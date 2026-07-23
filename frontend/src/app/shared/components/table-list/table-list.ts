import { Component, computed, input, output } from '@angular/core';
import { MatTableModule } from '@angular/material/table';
import { TableAction, TableActionEvent, TableListSettings, TableSortSettings } from '../../types/table-list.type';
import { MatIcon } from '@angular/material/icon';
import { MatSort, MatSortModule, Sort } from '@angular/material/sort';
import { LoadingSpinner } from "../loading-spinner/loading-spinner";
import { MatButtonModule } from '@angular/material/button';
import { FormatValuePipe } from '../../pipes/format-value.pipe';


@Component({
  selector: 'app-table-list',
  imports: [FormatValuePipe,MatTableModule, MatSortModule, MatSort, MatIcon, LoadingSpinner,MatButtonModule],
  templateUrl: './table-list.html',
  styleUrl: './table-list.scss',
})
export class TableList<T> {
  items = input<T[]>([]);
  listSettings = input<TableListSettings<T>[]>([]);
  sortSettings = input<TableSortSettings>();
  actions = input<TableAction<T>[]>([]);
  sortChange = output<Sort>();
  loading = input<boolean>(true);

  //pega apenas os ids para referencia com campos
  protected references = computed(() => [...this.listSettings().map(c => c.reference), 'acoes']);
  protected actionClick = output<TableActionEvent<T>>();

  onActionClick(name: string, item: T): void {
    this.actionClick.emit({ name, item });
  }

  onSortChange(sort: Sort): void {
    this.sortChange.emit(sort);
  }
}
