import { Injectable } from "@angular/core";

@Injectable()
export class NavigationService {
  currentModule = "content";
  public redirectUrl = "";
  public courseId: string = null;

  constructor() {}

  switchModule(module) {
    this.currentModule = module;
  }
}
