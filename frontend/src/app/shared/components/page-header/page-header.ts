import { Component, input, output } from '@angular/core';
import { MatAnchor } from "@angular/material/button";

@Component({
  selector: 'app-page-header',
  imports: [MatAnchor],
  templateUrl: './page-header.html',
  styleUrl: './page-header.scss',
})
export class PageHeader {
  title = input<string>('');
  subtitle = input<string>('');
  actionLabel = input<string>('');

  actionEvent = output<void>();
}
