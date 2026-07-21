import { HttpClient } from '@angular/common/http';
import { inject, Service } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../../environments/environment';
import { ApiResponse } from '../../../shared/types/api-response.type';
import { EntitySelectOption } from '../../../shared/types/entity-select-option.type';

@Service()
export class PacienteService {
  private httpClient = inject(HttpClient);
  
  private readonly API_ENDPOINT_PACIENTES = `${environment.apiAtendimentos}/api/pacientes`;

  getAllAsOptions():Observable<EntitySelectOption[]>{
    return this.httpClient
      .get<ApiResponse<EntitySelectOption[]>>(`${this.API_ENDPOINT_PACIENTES}/options`)
      .pipe(
        map(response => response.data ?? [])
      )
  }
}
