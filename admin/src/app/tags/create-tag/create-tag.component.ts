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
  selector: 'admin-create-tag',
  templateUrl: './create-tag.component.html'
})
export class CreateTagComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  tagForm = this.fb.group({
    id: [''],
    name: ['', Validators.required]
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository.find('tag', this.update.id).subscribe((result: any) => {
        this.loading = false;
        this.tagForm.patchValue(result);
      });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.tagForm.value.id;
      this.repository
        .create('tag', this.tagForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.tagForm.value);
        });
    } else {
      this.repository
        .update('tag', this.tagForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.tagForm.value);
        });
    }
  }
}
