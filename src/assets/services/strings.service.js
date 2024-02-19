class StringsService {
    compareStrings(a, b) {
        return a.localeCompare(b);
    }
}

export const stringsService = new StringsService();