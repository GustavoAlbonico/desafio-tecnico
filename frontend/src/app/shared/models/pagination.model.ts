export interface PaginationModel {
    currentPage: number,
    perPage: number,
    total: number,
    totalPages: number,
    hasNextPage: boolean,
    hasPrevPage: boolean,
}

export interface PaginationApiModel {
    current_page: number,
    per_page: number,
    total: number,
    total_pages: number,
    has_next_page: boolean,
    has_prev_page: boolean,
}

