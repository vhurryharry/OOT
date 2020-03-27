import { Component, OnInit } from "@angular/core";
import { FormBuilder } from "@angular/forms";
import { Location } from "@angular/common";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute, Router } from "@angular/router";
import { NavigationService } from "src/app/services/navigation.service";

@Component({
  selector: "admin-manage-testimonials",
  templateUrl: "./manage-testimonials.component.html"
})
export class ManageTestimonialsComponent implements OnInit {
  loading = false;
  testimonials = [];
  courseId: string = null;
  pageTitle = "Manage Course Testimonials";

  constructor(
    private fb: FormBuilder,
    private navigationService: NavigationService,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private location: Location,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params.id;
    });
  }

  ngOnInit() {
    if (this.courseId && this.courseId !== "0") {
      this.loading = true;
      this.repository
        .custom("course_testimonial", { id: this.courseId }, "findByCourse")
        .subscribe((result: any) => {
          this.loading = false;
          this.testimonials = result.items;
        });
    }
  }

  onEdit(id) {
    this.navigationService.redirectUrl =
      "/courses/edit/" + this.courseId + "/testimonials";
    this.router.navigateByUrl("/testimonials/edit/" + id);
  }

  onRemove(id) {
    this.loading = true;
    this.repository
      .delete("course_testimonial", [id])
      .subscribe((result: any) => {
        this.ngOnInit();
      });
  }

  onRestore(id) {
    this.loading = true;
    this.repository
      .restore("course_testimonial", [id])
      .subscribe((result: any) => {
        this.ngOnInit();
      });
  }

  goBack() {
    this.location.back();
  }

  onAdd() {
    this.navigationService.redirectUrl =
      "/courses/edit/" + this.courseId + "/testimonials";
    this.navigationService.courseId = this.courseId;
    this.router.navigateByUrl("/testimonials/edit/" + 0);
  }
}
