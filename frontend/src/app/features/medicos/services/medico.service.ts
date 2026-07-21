import { HttpClient } from '@angular/common/http';
import { inject, Service } from '@angular/core';
import { environment } from '../../../../environments/environment';
import { map, Observable } from 'rxjs';
import { EntitySelectOption } from '../../../shared/types/entity-select-option.type';
import { ApiResponse } from '../../../shared/types/api-response.type';

@Service()
export class MedicoService {
  private httpClient = inject(HttpClient);
  
  private readonly API_ENDPOINT_MEDICOS = `${environment.apiAtendimentos}/api/medicos`;

  getAllAsOptions():Observable<EntitySelectOption[]>{
    return this.httpClient
      .get<ApiResponse<EntitySelectOption[]>>(`${this.API_ENDPOINT_MEDICOS}/options`)
      .pipe(
        map(response => response.data ?? [])
      )
  }
}


