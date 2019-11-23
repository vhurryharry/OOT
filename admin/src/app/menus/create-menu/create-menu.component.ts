import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-menu',
  templateUrl: './create-menu.component.html'
})
export class CreateMenuComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  menuForm = this.fb.group({
    id: [''],
    title: ['', Validators.required],
    link: [''],
    displayOrder: [''],
    parent: [''],
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('menu', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.menuForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.menuForm.value.id;
      this.repository
        .create('menu', this.menuForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.menuForm.value);
        });
    } else {
      this.repository
        .update('menu', this.menuForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.menuForm.value);
        });
    }
  }
}
