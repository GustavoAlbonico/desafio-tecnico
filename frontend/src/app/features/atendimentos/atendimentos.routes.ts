import { Routes } from '@angular/router';

export const ATENDIMENTOS_ROUTES: Routes = [
  {
    path: '',
    loadComponent: () =>
      import('./pages/atendimento-list.page/atendimento-list.page')
        .then(atendimentos => atendimentos.AtendimentoListPage)
  },
  {
    path: 'novo',
    loadComponent: () =>
      import('./pages/atendimento-form.page/atendimento-form.page')
        .then(atendimentos => atendimentos.AtendimentoFormPage)
  },
  {
    path: 'editar/:id',
    loadComponent: () =>
      import('./pages/atendimento-form.page/atendimento-form.page')
        .then(atendimentos => atendimentos.AtendimentoFormPage)
  }
];
