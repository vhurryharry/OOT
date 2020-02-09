import {
  Component,
  Input,
  OnChanges,
  EventEmitter,
  Output,
  OnInit
} from "@angular/core";
import {
  FormArray,
  FormBuilder,
  FormGroup,
  FormControl,
  Validators
} from "@angular/forms";
import { RepositoryService } from "../../services/repository.service";
import { ActivatedRoute, Router } from "@angular/router";

@Component({
  selector: "admin-create-instructor",
  templateUrl: "./create-instructor.component.html"
})
export class CreateInstructorComponent implements OnInit {
  pageTitle = "";
  instructorId: string = null;
  loading = false;
  instructorForm = this.fb.group({
    id: [""],
    status: ["", Validators.required],
    confirmationToken: [""],
    acceptsMarketing: [""],
    expiresAt: [""],
    login: [""],
    passwordExpiresAt: [""],
    firstName: [""],
    lastName: [""],
    tagline: [""],
    occupation: [""],
    birthDate: [""],
    password: [""],
    mfa: [""]
  });

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.instructorId = params.id;
      if (params.id === "0") {
        this.instructorId = null;
      }

      if (this.instructorId) {
        this.pageTitle = "Edit Instructor";
      } else {
        this.pageTitle = "Add New Instructor";
      }
    });
  }

  ngOnInit() {
    if (this.instructorId) {
      this.loading = true;
      this.repository
        .find("customer", this.instructorId)
        .subscribe((result: any) => {
          this.loading = false;
          this.instructorForm.patchValue(result);
        });
    }
  }

  goBack() {
    this.router.navigate(["/instructors"]);
  }

  onSubmit() {
    this.loading = true;
    const payload = this.instructorForm.value;
    payload.type = "instructor";

    if (!this.instructorId) {
      delete payload.id;
      this.repository.create("customer", payload).subscribe((result: any) => {
        this.loading = false;
        this.router.navigate(["/instructors"]);
      });
    } else {
      this.repository.update("customer", payload).subscribe((result: any) => {
        this.loading = false;
        this.router.navigate(["/instructors"]);
      });
    }
  }
}
