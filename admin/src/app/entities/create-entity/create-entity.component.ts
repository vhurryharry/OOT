import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-entity',
  templateUrl: './create-entity.component.html'
})
export class CreateEntityComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  entityForm = this.fb.group({
    id: [''],
    slug: ['', Validators.required],
    category: [''],
    title: ['', Validators.required],
    type: ['', Validators.required],
    content: [''],
    metaTitle: [''],
    metaDescription: ['']
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('entity', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.entityForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.entityForm.value.id;
      this.repository
        .create('entity', this.entityForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.entityForm.value);
        });
    } else {
      this.repository
        .update('entity', this.entityForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.entityForm.value);
        });
    }
  }
}
