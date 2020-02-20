import { Component, OnInit } from "@angular/core";
import { FormBuilder, Validators } from "@angular/forms";
import { Location } from "@angular/common";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute } from "@angular/router";

@Component({
  selector: "admin-manage-categories",
  templateUrl: "./manage-categories.component.html"
})
export class ManageCategoriesComponent implements OnInit {
  loading = false;
  categories = [];
  availableCategories = [];
  categoryForm = this.fb.group({
    category: ["", Validators.required]
  });
  courseId: string = null;
  pageTitle = "Manage Course Categories";

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private location: Location
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params.id;
    });
  }

  ngOnInit() {
    this.loading = true;
    this.repository.fetch("course_category", {}).subscribe((result: any) => {
      this.availableCategories = result.items;
      this.loading = false;
    });

    this.loadCategories();
  }

  loadCategories() {
    if (this.courseId && this.courseId !== "0") {
      this.loading = true;
      this.repository
        .find("course/categories", this.courseId)
        .subscribe((result: any) => {
          this.loading = false;
          this.categories = result;
        });
    }
  }

  onSubmit() {
    this.loading = true;

    this.repository
      .create("course/categories", {
        course_id: this.courseId,
        category_id: this.categoryForm.value.category
      })
      .subscribe((result: any) => {
        this.loading = false;
        this.loadCategories();
      });
  }

  onRemove(id) {
    this.loading = true;

    this.repository
      .delete("course/categories", {
        course_id: this.courseId,
        category_id: id
      })
      .subscribe((result: any) => {
        this.loading = false;
        this.loadCategories();
      });
  }

  isAvailable(selectedInstructor) {
    for (const instructor of this.categories) {
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
