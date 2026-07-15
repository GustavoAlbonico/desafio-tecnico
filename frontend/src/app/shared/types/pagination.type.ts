export interface Pagination {
    currentPage: number,
    perPage: number,
    total: number,
    totalPages: number,
    hasNextPage: boolean,
    hasPrevPage: boolean,
}

export interface PaginationApi {
    current_page: number,
    per_page: number,
    total: number,
    total_pages: number,
    has_next_page: boolean,
    has_prev_page: boolean,
}