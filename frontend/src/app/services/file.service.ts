import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class FileService {
  saveAsCsv(items, filename) {
    const blob = new Blob([items], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');

    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
  }
}
