export type GetValueType<Choice> = (choice: Choice) => string;
export type FilterType<Choice> = (choice: Choice, query: string) => boolean;
export type GetFilterValue<Choice> = (choice: Choice) => string;
export type OnCreate<Choice> = (value: string) => Promise<Choice>;

export class ChoicesFilter<Choice> {
    private readonly filter: FilterType<Choice> | null = null;

    constructor(filter: FilterType<Choice> | null, getFilterValue: GetFilterValue<Choice> | null) {
        if (filter) {
            this.filter = filter;
        } else if (getFilterValue) {
            this.filter = (choice: Choice, query: string) => {
                return getFilterValue(choice).toLowerCase().includes(query.toLowerCase())
            };
        }
    }

    public getFilteredChoices(choices: Choice[], query: string) {
        if (this.filter === null) {
            throw Error('You must specify either a filter or getFilterValue function for filtering to work');
        }

        let filteredChoices = choices;
        if (query) {
            filteredChoices = choices.filter(choice => this.filter(choice, query));
        }

        return filteredChoices;
    }

    public hasFilter(): boolean {
        return this.filter !== null;
    }
}

export class ChoiceHandler<Choice> {
    public readonly choicesMap: Map<string, Choice>;

    constructor(choices: Choice[], getValue: GetValueType<Choice>) {
        this.choicesMap = this.mapChoices(choices, getValue);
    }

    private mapChoices(choices: Choice[], getValue: GetValueType<Choice>): Map<string, Choice> {
        return new Map(choices.map(choice => [getValue(choice), choice]));
    }
}
