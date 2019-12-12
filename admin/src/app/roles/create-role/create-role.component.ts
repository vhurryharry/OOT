import {
  Component,
  Input,
  OnChanges,
  EventEmitter,
  Output
} from '@angular/core';
import {
  FormArray,
  FormBuilder,
  FormGroup,
  FormControl,
  Validators
} from '@angular/forms';
import { RepositoryService } from '../../services/repository.service';

@Component({
  selector: 'admin-create-role',
  templateUrl: './create-role.component.html'
})
export class CreateRoleComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  roleForm = this.fb.group({
    id: [''],
    name: ['', Validators.required],
    parent: [''],
    permissions: ['']
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository.find('role', this.update.id).subscribe((result: any) => {
        this.loading = false;
        this.roleForm.patchValue(result);
      });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.roleForm.value.id;
      this.repository
        .create('role', this.roleForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.roleForm.value);
        });
    } else {
      this.repository
        .update('role', this.roleForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.roleForm.value);
        });
    }
  }
}
