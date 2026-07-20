import { Component, input, effect, viewChild, signal, Self, Optional, computed } from '@angular/core';
import { FormControl, ReactiveFormsModule, ControlValueAccessor, NgControl} from '@angular/forms';
import { Observable } from 'rxjs';
import {  map, startWith } from 'rxjs/operators';
import { AsyncPipe } from '@angular/common';
import { MatAutocomplete, MatAutocompleteModule, MatAutocompleteSelectedEvent } from '@angular/material/autocomplete';
import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import { EntitySelectOption } from '../../types/entity-select-option.type';
import { getErrorFromControl } from '../../constants/form-errors.constant';

@Component({
  selector: 'app-entity-select',
  imports: [
    MatFormFieldModule,
    MatInputModule,
    MatAutocompleteModule,
    ReactiveFormsModule,
    AsyncPipe,
  ],
  templateUrl: './entity-select.html',
  styleUrl: './entity-select.scss',
})
export class EntitySelect implements ControlValueAccessor {
  autoPanel = viewChild<MatAutocomplete>('auto');

  myControl = new FormControl<string | EntitySelectOption>('');
  filteredOptions: Observable<EntitySelectOption[]>;

  label = input<string>('');
  options = input<EntitySelectOption[]>([]);
  required = input<boolean>(false);

  private selectedId = signal<number | null>(null);
  private onChange: (value: number | null) => void = () => {};
  private onTouched: () => void = () => {};

  constructor(@Self() @Optional() public ngControl: NgControl | null) {
    if (this.ngControl) {
      this.ngControl.valueAccessor = this;
    }

    this.filteredOptions = this.myControl.valueChanges.pipe(
      startWith(''),
      map(value => {
        const name = typeof value === 'string' ? value : value?.nome;
        return name ? this._filter(name) : this.options();
      }),
    );

    this.myControl.valueChanges.subscribe(value => {
      if (typeof value === 'string') {
        this.selectedId.set(null);
        this.onChange(null);
      }
    });

    effect(() => {
      const currentValue = this.selectedId();
      this.options(); /* para atualizar depois da requisição */

      if (currentValue === null) {
        this.myControl.setValue('');
        this.autoPanel()?.options?.forEach(option => option.deselect());
        return;
      }

      const selectedOption = this.options().find(option => option.id === currentValue);
      if (selectedOption) {
        this.myControl.setValue(selectedOption, { emitEvent: false });
      }
    });
  }

  displayFn(option: EntitySelectOption): string {
    return option && option.nome ? option.nome : '';
  }

  onOptionSelected(event: MatAutocompleteSelectedEvent): void {
    const selected = event.option.value as EntitySelectOption;
    this.selectedId.set(selected.id);
    this.onChange(selected.id);
  }

  onBlur(): void {
    this.onTouched();
  }

  getError(): string | null {
    return getErrorFromControl(this.ngControl?.control ?? null);
  }

  writeValue(value: number | null): void {
    this.selectedId.set(value);
  }

  registerOnChange(fn: (value: number | null) => void): void {
    this.onChange = fn;
  }

  registerOnTouched(fn: () => void): void {
    this.onTouched = fn;
  }

  setDisabledState(isDisabled: boolean): void {
    if (isDisabled) {
      this.myControl.disable();
    } else {
      this.myControl.enable();
    }
  }
    
  private _filter(name: string): EntitySelectOption[] {
    const filterValue = name.toLowerCase();
    return this.options().filter(option => option.nome.toLowerCase().includes(filterValue));
  }
}