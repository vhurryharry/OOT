import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-create-user',
  templateUrl: './create-user.component.html'
})
export class CreateUserComponent {
  @Output()
  onFinish = new EventEmitter();

  loading = false;
  userForm = this.fb.group({
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

  get permissions() {
    return this.userForm.get('permissions') as FormArray;
  }

  addPermission() {
    this.permissions.push(this.fb.control(''));
  }

  onSubmit() {
    this.loading = true;
    this.repository
      .create('user', this.userForm.value)
      .subscribe((result: any) => {
        this.loading = false;
        this.onFinish.emit(this.userForm.value);
      });
  }
}
