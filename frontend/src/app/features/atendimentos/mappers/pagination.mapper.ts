import { Pagination, PaginationApi } from "../../../shared/types/pagination.type";


export const mapPagination = (paginationApi: PaginationApi): Pagination => {
  return {
    currentPage: paginationApi.current_page,
    perPage: paginationApi.per_page,
    total: paginationApi.total,
    totalPages: paginationApi.total_pages,
    hasNextPage: paginationApi.has_next_page,
    hasPrevPage: paginationApi.has_prev_page,
  };
}