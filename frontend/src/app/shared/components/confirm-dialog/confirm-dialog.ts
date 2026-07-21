import { Component, inject } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import {
  MatDialogActions,
  MatDialogContent,
  MatDialogTitle,
  MatDialogClose,
  MAT_DIALOG_DATA,
} from '@angular/material/dialog';

export interface ConfirmDialogData {
  title: string;
  description: string;
  leftButtonLabel: string;
  rightButtonLabel: string;
}

@Component({
  selector: 'app-confirm-dialog',
  imports: [MatDialogActions, MatDialogContent, MatDialogTitle, MatButtonModule,MatDialogClose],
  templateUrl: './confirm-dialog.html',
  styleUrl: './confirm-dialog.scss',
})
export class ConfirmDialog {
  protected data = inject<ConfirmDialogData>(MAT_DIALOG_DATA);
}