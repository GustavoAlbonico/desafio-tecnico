import { PaginationApiModel } from "./pagination.model";

export interface ApiResponseModel<T> {
    success:boolean,
    message:string,
    data?:T[] | T | null,
    pagination?: PaginationApiModel,
    errors?: ApiErrors | null
}

export interface ApiErrors {
  [campo: string]: {
    [regra: string]: string;
  };
}