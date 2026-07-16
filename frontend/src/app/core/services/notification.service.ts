import { inject, Service } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';

@Service()
export class NotificationService {
    private snackBar = inject(MatSnackBar);

    showSuccess(message: string): void {
        this.snackBar.open(message, '', {
        duration: 3000,
        panelClass: ['snackbar-success'],
        });
    }

    showError(message: string): void {
        this.snackBar.open(message, '', {
        duration: 5000,
        panelClass: ['snackbar-error'],
        });
    }
}
