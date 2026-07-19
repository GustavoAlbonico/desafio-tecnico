import { Routes } from '@angular/router';

export const ATENDIMENTOS_ROUTES: Routes = [
  {
    path: '',
    loadComponent: () =>
      import('./pages/atendimento-list.page/atendimento-list.page')
        .then(atendimentos => atendimentos.AtendimentoListPage)
  },
];
