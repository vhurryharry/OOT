import { Component, OnInit } from "@angular/core";
import { FormBuilder, Validators } from "@angular/forms";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute, Router } from "@angular/router";
import { NavigationService } from "src/app/services/navigation.service";

declare var $: any;

@Component({
  selector: "admin-create-testimonial",
  templateUrl: "./create-testimonial.component.html",
  styleUrls: ["./create-testimonial.component.css"]
})
export class CreateTestimonialComponent implements OnInit {
  pageTitle = "";
  testimonialId: string = null;
  loading = false;
  avatarSource: File = null;
  authorAvatar = "";
  testimonialForm = this.fb.group({
    id: [""],
    testimonial: ["", Validators.required],
    author: ["", Validators.required],
    authorOccupation: ["", Validators.required],
    authorAvatar: [""]
  });
  redirectUrl = "/testimonials";
  courseId: string = null;

  constructor(
    private fb: FormBuilder,
    private navigationService: NavigationService,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.testimonialId = params.id;
      if (params.id === "0") {
        this.testimonialId = null;
      }

      if (this.testimonialId) {
        this.pageTitle = "Edit Testimonial";
      } else {
        this.pageTitle = "Create New Testimonial";
      }
    });

    if (
      this.navigationService.redirectUrl &&
      this.navigationService.redirectUrl !== ""
    ) {
      this.redirectUrl = this.navigationService.redirectUrl;
      this.courseId = this.navigationService.courseId;
      this.navigationService.redirectUrl = "";
      this.navigationService.courseId = null;
    }
  }

  ngOnInit() {
    if (this.testimonialId) {
      this.loading = true;
      this.repository
        .find("course_testimonial", this.testimonialId)
        .subscribe((result: any) => {
          this.loading = false;

          this.authorAvatar = result.authorAvatar
            ? result.authorAvatar
            : "/admin/assets/avatar.png";

          this.testimonialForm.patchValue(result);
        });
    }
  }

  goBack() {
    this.router.navigate([this.redirectUrl]);
  }

  onUpdateAvatar() {
    $("#account-profile-avatar").trigger("click");
  }

  onSubmit() {
    this.loading = true;

    if (!this.testimonialId) {
      delete this.testimonialForm.value.id;

      const data = {
        ...this.testimonialForm.value,
        authorAvatar: "",
        course: this.courseId
      };

      this.repository
        .create("course_testimonial", data)
        .subscribe((result: any) => {
          if (this.avatarSource) {
            this.repository
              .uploadFile("course_testimonial", this.avatarSource, result.id)
              .subscribe((res: any) => {
                this.loading = false;
                this.router.navigate([this.redirectUrl]);
              });
          } else {
            this.loading = false;
            this.router.navigate([this.redirectUrl]);
          }
        });
    } else {
      this.testimonialForm.value.authorAvatar = "";
      this.repository
        .update("course_testimonial", this.testimonialForm.value)
        .subscribe((result: any) => {
          if (this.avatarSource) {
            this.repository
              .uploadFile("course_testimonial", this.avatarSource, result.id)
              .subscribe((res: any) => {
                this.loading = false;
                this.router.navigate([this.redirectUrl]);
              });
          } else {
            this.loading = false;
            this.router.navigate([this.redirectUrl]);
          }
        });
    }
  }

  onUpdateAvatarSource($event: Event) {
    this.avatarSource = ($event.target as any).files[0] as File;

    const reader = new FileReader();
    reader.onload = (e: any) => {
      this.authorAvatar = e.target.result;
    };

    // This will process our file and get it"s attributes/data
    reader.readAsDataURL(this.avatarSource);
  }
}
