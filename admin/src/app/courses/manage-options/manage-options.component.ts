import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';
import slugify from 'slugify';

@Component({
  selector: 'admin-manage-options',
  templateUrl: './manage-options.component.html'
})
export class ManageOptionsComponent implements OnChanges {
  @Input()
  update: any;

  loading = false;
  options = [];
  optionForm = this.fb.group({
    id: [''],
    name: ['', Validators.required],
    parent: ['', ],
    permissions: ['', ],
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnChanges() {
    if (!this.update || !this.update.id) {
      return;
    }

    this.loading = true;
    this.repository
      .find('course/options', this.update.id)
      .subscribe((result: any) => {
        this.loading = false;
        this.options = result;
        this.optionForm.patchValue(result);
      });
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.optionForm.value.id;
      this.repository
        .create('course/options', this.optionForm.value)
        .subscribe((result: any) => {
          this.loading = false;
        });
    } else {
      this.repository
        .update('course/options', this.optionForm.value)
        .subscribe((result: any) => {
          this.loading = false;
        });
    }
  }
}
