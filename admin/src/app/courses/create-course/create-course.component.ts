import { Component, Input, OnChanges, EventEmitter, Output } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { RepositoryService } from '../../repository.service';
import slugify from 'slugify';
import * as ClassicEditor from '@ckeditor/ckeditor5-build-classic';

@Component({
  selector: 'admin-create-course',
  templateUrl: './create-course.component.html'
})
export class CreateCourseComponent implements OnChanges {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  courseForm = this.fb.group({
    id: [''],
    title: ['', Validators.required],
    slug: ['', Validators.required],
    tagline: [''],
    metaTitle: [''],
    metaDescription: [''],
    thumbnail: [''],
    hero: [''],
    content: [''],
    program: [''],
    location: [''],
    address: [''],
    city: [''],
    startDate: ['', Validators.required],
    spots: ['', Validators.required],
  });
  public editor = ClassicEditor;

  constructor(private fb: FormBuilder, private repository: RepositoryService) {
    this.courseForm.get('title').valueChanges.subscribe(val => {
      if (!val) { return; }

      this.courseForm.patchValue({ slug: slugify(val, { lower: true }) });
    });
  }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository
        .find('course', this.update.id)
        .subscribe((result: any) => {
          this.loading = false;
          this.courseForm.patchValue(result);
        });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.courseForm.value.id;
      this.repository
        .create('course', this.courseForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.courseForm.value);
        });
    } else {
      this.repository
        .update('course', this.courseForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.courseForm.value);
        });
    }
  }
}
