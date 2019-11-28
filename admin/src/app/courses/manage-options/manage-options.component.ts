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
    title: ['', Validators.required],
    price: ['', Validators.required],
    combo: [false],
    dates: this.fb.array([
      this.fb.control('')
    ])
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
    const payload = this.optionForm.value;
    payload.course = this.update.id;

    if (!this.update) {
      delete payload.id;
      this.repository
        .create('course/options', payload)
        .subscribe((result: any) => {
          this.loading = false;
          this.ngOnChanges();
        });
    } else {
      this.repository
        .update('course/options', payload)
        .subscribe((result: any) => {
          this.loading = false;
          this.ngOnChanges();
        });
    }
  }

  onRemove(id) {
    this.loading = true;
    this.repository
      .delete('course', [id])
      .subscribe((result: any) => {
        this.ngOnChanges();
      });
  }

  get dates() {
    return this.optionForm.get('dates') as FormArray;
  }

  addDate() {
    this.dates.push(this.fb.control(''));
  }
}
