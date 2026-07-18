import { RouterOutlet } from '@angular/router';
import { Sidebar } from '../sidebar/sidebar';
import { MediaMatcher } from '@angular/cdk/layout';
import { Component, OnDestroy, inject, signal } from '@angular/core';
import { MatListModule } from '@angular/material/list';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatToolbarModule } from '@angular/material/toolbar';
import { Header } from '../header/header';

@Component({
  selector: 'app-auth-layout',
  imports: [
    RouterOutlet,
    Sidebar,
    Header,
    MatToolbarModule,
    MatButtonModule,
    MatIconModule,
    MatSidenavModule,
    MatListModule
  ],
  templateUrl: './auth-layout.html',
  styleUrl: './auth-layout.scss',
})
export class AuthLayout {
  protected readonly isMobile = signal(false);

  private readonly _mobileQuery: MediaQueryList;
  private readonly _mobileQueryListener: () => void;

  constructor() {
    const media = inject(MediaMatcher);

    this._mobileQuery = media.matchMedia('(max-width: 720px)'); //define um tamanho mobile padrão
    this.isMobile.set(this._mobileQuery.matches);
    this._mobileQueryListener = () => this.isMobile.set(this._mobileQuery.matches); //verifica se está no tamanho mobile
    this._mobileQuery.addEventListener('change', this._mobileQueryListener); //evento ao trocar de tamanho de tela
  }

  ngOnDestroy(): void {
    this._mobileQuery.removeEventListener('change', this._mobileQueryListener);
  }
}
