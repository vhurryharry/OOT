import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { Location } from '@angular/common';
import { RepositoryService } from '../../services/repository.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'admin-manage-instructors',
  templateUrl: './manage-instructors.component.html'
})
export class ManageInstructorsComponent implements OnInit {
  loading = false;
  instructors = [];
  availableInstructors = [];
  instructorForm = this.fb.group({
    instructor: ['', Validators.required]
  });
  courseId: string = null;
  pageTitle: string = 'Manage Course Instructors';

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private location: Location
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params['id'];
    });
  }

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

    this.loadInstructors();
  }

  loadInstructors() {
    if (this.courseId && this.courseId != '0') {
      this.loading = true;
      this.repository
        .find('course/instructor', this.courseId)
        .subscribe((result: any) => {
          this.loading = false;
          this.instructors = result;
        });
    }
  }

  onSubmit() {
    this.loading = true;

    this.repository
      .attach('course_instructor', {
        course_id: this.courseId,
        customer_id: this.instructorForm.value.instructor
      })
      .subscribe((result: any) => {
        this.loading = false;
        this.loadInstructors();
      });
  }

  onRemove(id) {
    this.loading = true;

    this.repository
      .detach('course_instructor', { id })
      .subscribe((result: any) => {
        this.loading = false;
        this.loadInstructors();
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

  goBack() {
    this.location.back();
  }
}
