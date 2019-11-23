import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-category',
  templateUrl: './create-category.component.html'
})
export class CreateCategoryComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  categoryForm = this.fb.group({
    id: [''],
    name: ['', Validators.required],
    parent: [''],
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('category', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.categoryForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.categoryForm.value.id;
      this.repository
        .create('category', this.categoryForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.categoryForm.value);
        });
    } else {
      this.repository
        .update('category', this.categoryForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.categoryForm.value);
        });
    }
  }
}
