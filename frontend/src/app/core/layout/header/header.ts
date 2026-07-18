import { Component, EventEmitter, input, output} from '@angular/core';
import { MatListModule } from '@angular/material/list';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatToolbarModule } from '@angular/material/toolbar';


@Component({
  selector: 'app-header',
  imports: [
    MatToolbarModule,
    MatButtonModule,
    MatIconModule,
    MatSidenavModule,
    MatListModule,
],
  templateUrl: './header.html',
  styleUrl: './header.scss',
})
export class Header {
  isMobile = input<boolean>(false);
  
  protected menuClick = output<PointerEvent>();

  onMenuClick(event:PointerEvent): void {
    this.menuClick.emit(event);
  }
}
