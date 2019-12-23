import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { RepositoryService } from '../../services/repository.service';
import slugify from 'slugify';
import * as ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'admin-create-entity',
  templateUrl: './create-entity.component.html'
})
export class CreateEntityComponent implements OnInit {
  pageTitle = '';
  entityId: string = null;
  loading = false;
  entityForm = this.fb.group({
    id: [''],
    slug: ['', Validators.required],
    category: [''],
    title: ['', Validators.required],
    type: ['', Validators.required],
    content: [''],
    metaTitle: [''],
    metaDescription: ['']
  });
  public editor = ClassicEditor;

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.entityId = params.id;
      if (params.id === '0') this.entityId = null;

      if (this.entityId) {
        this.pageTitle = 'Edit Page';
      } else {
        this.pageTitle = 'Create New Page';
      }
    });

    this.entityForm.get('title').valueChanges.subscribe(val => {
      if (!val) {
        return;
      }

      this.entityForm.patchValue({ slug: slugify(val, { lower: true }) });
    });
  }

  ngOnInit() {
    if (this.entityId) {
      this.loading = true;
      this.repository.find('entity', this.entityId).subscribe((result: any) => {
        this.loading = false;
        this.entityForm.patchValue(result);
      });
    }
  }

  goBack() {
    this.router.navigate(['/pages']);
  }

  onSubmit() {
    this.loading = true;

    if (!this.entityId) {
      delete this.entityForm.value.id;
      this.repository
        .create('entity', this.entityForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.router.navigate(['/pages']);
        });
    } else {
      this.repository
        .update('entity', this.entityForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.router.navigate(['/pages']);
        });
    }
  }
}
