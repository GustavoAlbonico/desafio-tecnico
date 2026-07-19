import { Component, input, output } from '@angular/core';
import {MatPaginatorModule, PageEvent} from '@angular/material/paginator';
import { Pagination } from '../../types/pagination.type';

@Component({
  selector: 'app-paginator',
  imports: [MatPaginatorModule],
  templateUrl: './paginator.html',
  styleUrl: './paginator.scss',
})
export class Paginator {
  pagination = input<Pagination | null>(null);
  paginationPageOptions = input<number[]>([5,10,20,50]);
  pageEvent = output<PageEvent>();

  onPageEvent(event:PageEvent):void {
    this.pageEvent.emit(event);
  }
}
