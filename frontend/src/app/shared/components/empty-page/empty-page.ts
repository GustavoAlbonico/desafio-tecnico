import { Component } from '@angular/core';
import { MatProgressBarModule} from '@angular/material/progress-bar';

@Component({
  selector: 'app-empty-page',
  imports: [MatProgressBarModule],
  templateUrl: './empty-page.html',
  styleUrl: './empty-page.scss',
})
export class EmptyPage {}
