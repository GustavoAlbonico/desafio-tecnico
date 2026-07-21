import { ValidatorFn, AbstractControl } from "@angular/forms";

export function dateRangeValidator(startField: string, endField: string): ValidatorFn {
  return (group: AbstractControl): null => {
    const start = group.get(startField);
    const end = group.get(endField);

    if (!start || !end) return null;

    if (start?.value && end?.value && start.value > end.value) {
      start.setErrors({ dateRangeInvalid: true });
      return null;
    }

    if (start?.hasError('dateRangeInvalid')) {
      start.setErrors(null);
    }

    return null;
  };
}