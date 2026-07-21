import {
  CurrencyPipe,
  DatePipe,
  DecimalPipe,
  PercentPipe,
} from '@angular/common';
import { inject, LOCALE_ID, Pipe, PipeTransform } from '@angular/core';
import { TableColumnPipe } from '../types/table-list.type';

@Pipe({
  name: 'formatValue',
  standalone: true
})
export class FormatValuePipe implements PipeTransform {
  /* Injeta o ID de localização da aplicação (ex: pt-BR)  */
  private readonly locale = inject(LOCALE_ID);

  /* Instancia os pipes manualmente passando o locale */
  private readonly datePipe = new DatePipe(this.locale);
  private readonly currencyPipe = new CurrencyPipe(this.locale);
  private readonly decimalPipe = new DecimalPipe(this.locale);
  private readonly percentPipe = new PercentPipe(this.locale);

  transform(
    value: string | number,
    pipe?: TableColumnPipe
  ): string {

    if (value == null) {
      return '';
    }

    if (!pipe) {
      return String(value);
    }

    switch (pipe.type) {

      case 'text':
        return String(value);

      case 'date':
        return this.datePipe.transform(
          value,
          pipe.format ?? 'dd/MM/yyyy'
        ) ?? '';

      case 'datetime':
        return this.datePipe.transform(
          value,
          pipe.format ?? 'dd/MM/yyyy HH:mm'
        ) ?? '';

      case 'currency':
        return this.currencyPipe.transform(
          value,
          pipe.currencyCode ?? 'BRL'
        ) ?? '';

      case 'percent':
        return this.percentPipe.transform(
          value,
          pipe.digitsInfo
        ) ?? '';

      case 'number':
        return this.decimalPipe.transform(
          value,
          pipe.digitsInfo
        ) ?? '';

      case 'boolean':
        return Boolean(value)
          ? (pipe.trueLabel ?? 'Sim')
          : (pipe.falseLabel ?? 'Não');

      default:
        return String(value);
    }
  }
}