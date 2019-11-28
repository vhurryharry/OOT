import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-student',
  templateUrl: './create-student.component.html'
})
export class CreateStudentComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  @Input()
  type: string;

  loading = false;
  studentForm = this.fb.group({
    id: [''],
    status: ['', Validators.required],
    confirmationToken: [''],
    acceptsMarketing: [''],
    expiresAt: [''],
    login: ['', Validators.required],
    passwordExpiresAt: [''],
    firstName: [''],
    lastName: [''],
    tagline: [''],
    occupation: [''],
    birthDate: [''],
    password: [''],
    mfa: [''],
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('customer', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.studentForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;
    const payload = this.studentForm.value;
    payload.type = 'student';

    if (!this.update) {
      delete payload.id;
      this.repository
        .create('customer', payload)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(payload);
        });
    } else {
      this.repository
        .update('customer', payload)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(payload);
        });
    }
  }
}
