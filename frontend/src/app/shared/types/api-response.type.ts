import { PaginationApi } from "./pagination.type";

export interface ApiResponseBase {
  success: boolean,
  message: string,
  errors?: ApiErrors | null,
}

export interface ApiPaginatedResponse<T> extends ApiResponseBase {
  data?: T[] | null,
  pagination?: PaginationApi,
}

export interface ApiResponse<T> extends ApiResponseBase {
  data?: T | null,
}

export interface ApiErrors {
  [campo: string]: {
    [regra: string]: string,
  },
}