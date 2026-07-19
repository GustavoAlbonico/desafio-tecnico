import { ApplicationConfig, provideBrowserGlobalErrorListeners } from '@angular/core';
import { provideNativeDateAdapter } from '@angular/material/core';
import { provideRouter } from '@angular/router';

import { routes } from './app.routes';
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { errorInterceptor } from './core/interceptors/error.interceptor';
import { MatPaginatorIntl } from '@angular/material/paginator';
import { getPaginatorIntlPtBr } from './core/i18n/paginator-intl-pt-br';
import { LOCALE_ID } from '@angular/core';
import { registerLocaleData } from '@angular/common';
import localePt from '@angular/common/locales/pt';

registerLocaleData(localePt)

export const appConfig: ApplicationConfig = {
  providers: [
    provideBrowserGlobalErrorListeners(),
    provideRouter(routes),
    provideHttpClient(withInterceptors([errorInterceptor])), 
    { provide: MatPaginatorIntl, useFactory: getPaginatorIntlPtBr }, /* cololocar em pt-br pagination componente*/
    provideNativeDateAdapter(), /* funcionar datepicker */
    { provide: LOCALE_ID, useValue: 'pt-BR' }, /* cololocar em pt-br datepicker*/
  ]
};
