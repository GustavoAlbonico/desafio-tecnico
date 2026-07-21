import { HttpClient } from '@angular/common/http';
import { inject, Service } from '@angular/core';
import { environment } from '../../../../environments/environment';
import { map, Observable } from 'rxjs';
import { AtendimentoApiListModel, AtendimentoApiModel, AtendimentoListModel, AtendimentoModel } from '../models/atendimento.model';
import { mapAtendimento, mapAtendimentoList } from '../mappers/atendimento.mapper';
import { PaginatedModel, PaginationParams } from '../../../shared/types/pagination.type';
import { ApiPaginatedResponse, ApiResponse } from '../../../shared/types/api-response.type';
import { mapPagination } from '../mappers/pagination.mapper';
import { FilterParams } from '../../../shared/types/filter-params.type';


@Service()
export class AtendimentoService {
  private httpClient = inject(HttpClient);

  private readonly API_ENDPOINT_ATENDIMENTOS = `${environment.apiAtendimentos}/api/atendimentos`;

  getAll(
    paginationParams:PaginationParams | null = null,
    filterParams:FilterParams | null = null
  ):Observable<PaginatedModel<AtendimentoListModel>>
  {
    return this.httpClient
      .get<ApiPaginatedResponse<AtendimentoApiListModel>>(this.API_ENDPOINT_ATENDIMENTOS,{
        params: {...paginationParams, ...filterParams}
      })
      .pipe(
        map(response => {
          const items = (response.data ?? []).map(mapAtendimentoList);
          const pagination = mapPagination(response.pagination!);

          return { items, pagination };
        })
      )
  }

  getById(id: number): Observable<AtendimentoApiModel> {
    return this.httpClient
      .get<ApiResponse<AtendimentoApiModel>>(`${this.API_ENDPOINT_ATENDIMENTOS}/${id}`)
      .pipe(
           map(response => {
             if (!response.data) {
              throw new Error('Atendimento não encontrado.');
            }
            return response.data;
          }),
      );
  }
  
  create(atendimentoApiModel:AtendimentoApiModel):Observable<ApiResponse<AtendimentoModel>>{
    return this.httpClient
      .post<ApiResponse<AtendimentoApiModel>>(this.API_ENDPOINT_ATENDIMENTOS,atendimentoApiModel)
      .pipe(
        map(response => {
          if (!response.data) {
            throw new Error('Atendimento editado não encontrado.');
          }

          return {
            ...response,
            data: mapAtendimento(response.data),
          };
        })
      );
  }

  update( id:number, atendimentoApiModel:AtendimentoApiModel):Observable<ApiResponse<AtendimentoModel>>{
    return this.httpClient
      .put<ApiResponse<AtendimentoApiModel>>(`${this.API_ENDPOINT_ATENDIMENTOS}/${id}`,atendimentoApiModel)
      .pipe(
        map(response => {
          if (!response.data) {
            throw new Error('Atendimento editado não encontrado.');
          }

          return {
            ...response,
            data: mapAtendimento(response.data),
          };
        })
      );
  }

  delete(id:number):Observable<ApiResponse<null>>{
    return this.httpClient.delete<ApiResponse<null>>(`${this.API_ENDPOINT_ATENDIMENTOS}/${id}`)
  }
  
}
