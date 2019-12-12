import {
  Component,
  Input,
  OnChanges,
  OnInit,
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
import slugify from 'slugify';

@Component({
  selector: 'admin-manage-instructors',
  templateUrl: './manage-instructors.component.html'
})
export class ManageInstructorsComponent implements OnChanges, OnInit {
  @Input()
  update: any;

  loading = false;
  instructors = [];
  availableInstructors = [];
  instructorForm = this.fb.group({
    instructor: ['', Validators.required]
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnInit() {
    this.loading = true;
    this.repository
      .fetch('customer', {
        filters: [
          {
            property: 'type',
            value: 'instructor'
          }
        ]
      })
      .subscribe((result: any) => {
        this.availableInstructors = result.items;
        this.loading = false;
      });
  }

  ngOnChanges() {
    if (!this.update) {
      return;
    }

    this.loading = true;
    this.repository
      .find('course/instructor', this.update.id)
      .subscribe((result: any) => {
        this.instructors = result;
        this.loading = false;
      });
  }

  onSubmit() {
    this.loading = true;

    this.repository
      .attach('course_instructor', {
        course_id: this.update.id,
        customer_id: this.instructorForm.value.instructor
      })
      .subscribe((result: any) => {
        this.loading = false;
        this.ngOnChanges();
      });
  }

  onRemove(id) {
    this.loading = true;

    this.repository
      .detach('course_instructor', { id })
      .subscribe((result: any) => {
        this.loading = false;
        this.ngOnChanges();
      });
  }

  isAvailable(selectedInstructor) {
    for (const instructor of this.instructors) {
      if (instructor.id === selectedInstructor.id) {
        return false;
      }
    }

    return true;
  }
}
