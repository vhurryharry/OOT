import {
  Component,
  Input,
  OnChanges,
  OnInit,
  EventEmitter,
  Output
} from "@angular/core";
import {
  FormArray,
  FormBuilder,
  FormGroup,
  FormControl,
  Validators
} from "@angular/forms";
import { RepositoryService } from "../../services/repository.service";

@Component({
  selector: "admin-create-menu",
  templateUrl: "./create-menu.component.html"
})
export class CreateMenuComponent implements OnChanges, OnInit {
  @Output()
  finished = new EventEmitter();

  @Input()
  update: any;

  loading = false;
  menus = [];
  menuForm = this.fb.group({
    id: [""],
    title: ["", Validators.required],
    link: [""],
    displayOrder: [0],
    parent: [""]
  });

  constructor(private fb: FormBuilder, private repository: RepositoryService) {}

  ngOnInit() {
    this.loading = true;
    this.repository.fetch("menu", {}).subscribe((result: any) => {
      this.menus = result.items;
      this.loading = false;
    });
  }

  ngOnChanges() {
    if (this.update) {
      this.loading = true;
      this.repository.find("menu", this.update.id).subscribe((result: any) => {
        this.loading = false;
        this.menuForm.patchValue(result);
      });
    }
  }

  onSubmit() {
    this.loading = true;

    if (!this.update) {
      delete this.menuForm.value.id;
      this.repository
        .create("menu", this.menuForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.menuForm.value);
        });
    } else {
      this.repository
        .update("menu", this.menuForm.value)
        .subscribe((result: any) => {
          this.loading = false;
          this.finished.emit(this.menuForm.value);
        });
    }
  }
}
