import { AbstractControl, FormControl, FormGroup, FormGroupDirective, NgForm } from "@angular/forms";
import { ApiErrors } from "../types/api-response.type";

export const applyErrorsToForm = (
  form: FormGroup,
  errors: ApiErrors
): void => {

  Object.entries(errors).forEach(([fieldName, fieldErrors]) => {

    const control = form.get(fieldName);

    if (!control) {
      return;
    }

    control.setErrors({
      ...control.errors,
      ...fieldErrors
    });

    control.markAsTouched();
  }
  );

};

export const getControlError = (
  form: FormGroup,
  controlName: string
): string | null => {
  return getErrorFromControl(form.get(controlName));
};

export const getErrorFromControl = (
  control: AbstractControl | null
): string | null => {
  if (!control?.errors) {
    return null;
  }

  const firstError = Object.values(control.errors)[0];
  return typeof firstError === 'string'
    ? firstError
    : 'Campo Obrigatório.';
};