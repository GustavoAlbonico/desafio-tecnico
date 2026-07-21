import { SortDirection } from "@angular/material/sort"

export interface TableListSettings<T> {
  reference: string,
  columnName: string,
  value: (item: T) => string | number,
  align?: 'left' | 'center' | 'right',
  class?: (item: T) => string,
  pipe?: TableColumnPipe
}

export interface TableAction {
  name: string,
  icon: string,
  label: string,
}

export interface TableActionEvent<T> {
  name: string,
  item: T,
}

export interface TableSortSettings {
  sort: string,
  direction: SortDirection
}

export type TableColumnPipe =
  | {type: 'text',}
  | {type: 'date',format?: string}
  | {type: 'datetime',format?: string}
  | {type: 'currency',currencyCode?: string}
  | {type: 'percent',digitsInfo?: string}
  | {type: 'number',digitsInfo?: string}
  | {type: 'boolean',trueLabel?: string,falseLabel?: string};
