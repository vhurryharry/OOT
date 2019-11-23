import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-document',
  templateUrl: './create-document.component.html'
})
export class CreateDocumentComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  documentForm = this.fb.group({
    id: [''],
    title: ['', Validators.required],
    content: ['', ],
    type: ['', Validators.required],
    source: ['', Validators.required],
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('document', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.documentForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.documentForm.value.id;
      this.repository
        .create('document', this.documentForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.documentForm.value);
        });
    } else {
      this.repository
        .update('document', this.documentForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.documentForm.value);
        });
    }
  }
}
