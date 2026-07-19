import { DateStringFormat, DateStringSeparator } from "../types/date-string.type";

export const formatDateToString = (
  date: Date,
  format: DateStringFormat,
  separator: DateStringSeparator = '/'
): string => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');

  switch (format) {
    case "MDY":
      return `${month}${separator}${day}${separator}${year}`;
    case "DMY":
      return `${day}${separator}${month}${separator}${year}`;
    case "YMD":
      return `${year}${separator}${month}${separator}${day}`;
    default: return '';
  }
}

export const formatStringDateToDate = (
  stringDate: string,
  format: DateStringFormat,
  separator: DateStringSeparator = '/'
): Date => {

  /* Divide a string nos pedaços de dia, mês e ano usando o separador definido */
  const parts = stringDate.split(separator);

  let year = 1970;
  let month = 0;  /* No JavaScript, os meses vão de 0 (Janeiro) a 11 (Dezembro) */
  let day = 1;

  switch (format) {
    case "MDY":
      month = parseInt(parts[0], 10) - 1;  /* Subtrai 1 para ajustar ao padrão do JS */
      day = parseInt(parts[1], 10);
      year = parseInt(parts[2], 10);
      break;
    case "DMY":
      day = parseInt(parts[0], 10);
      month = parseInt(parts[1], 10) - 1;
      year = parseInt(parts[2], 10);
      break;
    case "YMD":
      year = parseInt(parts[0], 10);
      month = parseInt(parts[1], 10) - 1;
      day = parseInt(parts[2], 10);
      break;
  }

  /* Cria e retorna o objeto Date nativo do JavaScript */
  return new Date(year, month, day);
}

export const formatStringDate = (
  stringDate: string,
  currentFormat: DateStringFormat,
  targetFormat: DateStringFormat,
  currentSeparator: DateStringSeparator = '/',
  targetSeparator: DateStringSeparator = '/'
): string => {

  const parts = stringDate.split(currentSeparator);

  let year = '';
  let month = '';
  let day = '';

  switch (currentFormat) {
    case 'MDY':
      [month, day, year] = parts;
      break;

    case 'DMY':
      [day, month, year] = parts;
      break;

    case 'YMD':
      [year, month, day] = parts;
      break;
  }

  switch (targetFormat) {
    case 'MDY':
      return [month, day, year].join(targetSeparator);

    case 'DMY':
      return [day, month, year].join(targetSeparator);

    case 'YMD':
      return [year, month, day].join(targetSeparator);
  }
};