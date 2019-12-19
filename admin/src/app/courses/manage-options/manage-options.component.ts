import { Component, OnInit } from '@angular/core';
import { FormArray, FormBuilder, Validators } from '@angular/forms';
import { Location } from '@angular/common';
import { RepositoryService } from '../../services/repository.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'admin-manage-options',
  templateUrl: './manage-options.component.html'
})
export class ManageOptionsComponent implements OnInit {
  loading = false;
  courseId: string = null;
  options = [];
  pageTitle: string = 'Manage Course Options';

  optionForm = this.fb.group({
    id: [''],
    title: ['', Validators.required],
    price: ['', Validators.required],
    combo: [false],
    dates: this.fb.array([this.fb.control('')])
  });

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
    if (this.courseId && this.courseId != '0') {
      this.loading = true;
      this.repository
        .find('course/options', this.courseId)
        .subscribe((result: any) => {
          this.loading = false;
          this.options = result;
        });
    }
  }

  onSubmit() {
    this.loading = true;
    const payload = this.optionForm.value;
    payload.course = this.courseId;

    delete payload.id;
    this.repository
      .create('course/options', payload)
      .subscribe((result: any) => {
        this.loading = false;
        this.ngOnInit();
      });
  }

  onRemove(id) {
    this.loading = true;
    this.repository.delete('course/options', [id]).subscribe((result: any) => {
      this.ngOnInit();
    });
  }

  get dates() {
    return this.optionForm.get('dates') as FormArray;
  }

  addDate() {
    this.dates.push(this.fb.control(''));
  }

  goBack() {
    this.location.back();
  }
}
