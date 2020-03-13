import { Component, OnInit } from "@angular/core";
import { FormBuilder, Validators } from "@angular/forms";
import { ActivatedRoute, Router } from "@angular/router";

import { RepositoryService } from "../../services/repository.service";
import slugify from "slugify";
import * as ClassicEditor from "@ckeditor/ckeditor5-build-classic";

@Component({
  selector: "admin-create-course",
  templateUrl: "./create-course.component.html",
  styleUrls: ["./create-course.component.scss"]
})
export class CreateCourseComponent implements OnInit {
  loading = false;
  courseId: string = null;
  pageTitle = "";

  courseForm = this.fb.group({
    id: [""],
    title: ["", Validators.required],
    slug: ["", Validators.required],
    tagline: [""],
    metaTitle: [""],
    metaDescription: [""],
    thumbnail: [""],
    hero: [""],
    content: [""],
    program: [""],
    location: [""],
    address: [""],
    city: [""],
    startDate: ["", Validators.required],
    spots: ["", Validators.required]
  });
  public editor = ClassicEditor;

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params.id;
      if (params.id === "0") {
        this.courseId = null;
      }

      if (this.courseId) {
        this.pageTitle = "Edit Course";
      } else {
        this.pageTitle = "Create New Course";
      }
    });

    this.courseForm.get("title").valueChanges.subscribe(val => {
      if (!val) {
        return;
      }

      this.courseForm.patchValue({ slug: slugify(val, { lower: true }) });
    });
  }

  ngOnInit() {
    if (this.courseId) {
      this.repository.find("course", this.courseId).subscribe((result: any) => {
        this.loading = false;
        this.courseForm.patchValue(result);

        const startDate = new Date(result.startDate);
        const startDateString =
          startDate.getMonth() +
          1 +
          "/" +
          startDate.getDate() +
          "/" +
          startDate.getFullYear();
        this.courseForm.patchValue({ startDate: startDateString });
      });
    }
  }

  goBack() {
    this.router.navigate(["/courses"]);
  }

  onSubmit() {
    this.loading = true;

    if (!this.courseId) {
      delete this.courseForm.value.id;
      this.repository
        .create("course", this.courseForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.router.navigate(["/courses"]);
        });
    } else {
      this.repository
        .update("course", this.courseForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.router.navigate(["/courses"]);
        });
    }
  }

  onOptions() {
    this.router.navigate(["/courses/edit/" + this.courseId + "/options"]);
  }

  onCategories() {
    this.router.navigate(["/courses/edit/" + this.courseId + "/categories"]);
  }

  onTopics() {
    this.router.navigate(["/courses/edit/" + this.courseId + "/topics"]);
  }

  onInstructors() {
    this.router.navigate(["/courses/edit/" + this.courseId + "/instructors"]);
  }

  onReviews() {
    this.router.navigate(["/courses/edit/" + this.courseId + "/reviews"]);
  }

  onTestimonials() {
    this.router.navigate(["/courses/edit/" + this.courseId + "/testimonials"]);
  }
}
