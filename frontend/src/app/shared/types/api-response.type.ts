import { Pagination } from "./pagination.type";

export interface ApiResponseBase {
  success: boolean;
  message: string;
  errors?: ApiErrors | null;
}

export interface ApiListResponse<T> extends ApiResponseBase {
  data?: T[] | null;
  pagination?: Pagination;
}

export interface ApiItemResponse<T> extends ApiResponseBase {
  data?: T | null;
}

export interface ApiErrors {
  [campo: string]: {
    [regra: string]: string;
  };
}