import { HttpErrorResponse, HttpEventType, HttpInterceptorFn } from '@angular/common/http';
import { catchError, tap, throwError } from 'rxjs';
import { ApiResponseBase } from '../../shared/types/api-response.type';
import { NotificationService } from '../services/notification.service';
import { inject } from '@angular/core';

export const notificationInterceptor: HttpInterceptorFn = (req, next) => {
  const notificationService = inject(NotificationService);

  return next(req).pipe(
    tap((event) => {

      if (event.type === HttpEventType.Response) {
        const apiResponseBase = event.body as ApiResponseBase;

        if(apiResponseBase?.success && apiResponseBase?.message){
          notificationService.showSuccess(apiResponseBase.message);
        }
      }

    }),

    catchError((error:HttpErrorResponse) => {
      const apiResponseBaseError = error.error as ApiResponseBase;
      const mensagemErro = apiResponseBaseError?.message ?? 'Occoreu um erro inesperado.';

      notificationService.showError(mensagemErro);
      console.error('Erro na requisição:', mensagemErro);

      return throwError(() => error);
    })
  );
};
