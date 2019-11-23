import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-user',
  templateUrl: './create-user.component.html'
})
export class CreateUserComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  userForm = this.fb.group({
    id: [''],
    name: ['', Validators.required],
    email: ['', Validators.required],
    firstName: [''],
    lastName: [''],
    password: ['', Validators.required],
    permissions: this.fb.array([
      this.fb.control('')
    ])
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) { }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('user', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.userForm.patchValue(result);
        });
    }
  }

  get permissions() {
    return this.userForm.get('permissions') as FormArray;
  }

  addPermission() {
    this.permissions.push(this.fb.control(''));
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.userForm.value['id'];
      this.repository
        .create('user', this.userForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.userForm.value);
        });
    } else {
      this.repository
        .update('user', this.userForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.userForm.value);
        });
    }
  }
}
