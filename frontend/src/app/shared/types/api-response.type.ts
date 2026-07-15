import { ApiErrors } from "./api-errors.type";
import { Pagination } from "./pagination.type";

export interface ApiResponse<T> {
    success:boolean,
    message:string,
    data?:T[] | T | null,
    pagination?: Pagination,
    errors?: ApiErrors | null
}
