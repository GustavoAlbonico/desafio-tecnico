import { inject, Service } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';

@Service()
export class NotificationService {
    private snackBar = inject(MatSnackBar);

    showSuccess(message: string): void {
        this.snackBar.open(message, 'Fechar', {
        horizontalPosition: 'right',
        verticalPosition: 'bottom',
        panelClass: ['snackbar-success'],
        });
    }

    showError(message: string): void {
        this.snackBar.open(message, 'Fechar', {
        horizontalPosition: 'right',
        verticalPosition: 'bottom',
        panelClass: ['snackbar-error'],
        });
    }
}
