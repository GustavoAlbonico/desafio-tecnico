import { Routes } from '@angular/router';
import { PublicLayout } from './core/layout/public-layout/public-layout';

export const routes: Routes = [
    {
        path: 'atendimentos',
        component: PublicLayout,
        loadChildren: () =>
            import('./features/atendimentos/atendimentos.routes')
                .then(atendimentos => atendimentos.ATENDIMENTOS_ROUTES),
    },
    { path: '**', redirectTo: 'atendimentos'},
];

